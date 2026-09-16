/**
 * Camera.js - Handle camera capture and processing
 */

class ChipCameraApp {
    constructor() {
        this.video = null;
        this.canvas = null;
        this.stream = null;
        this.isRunning = false;
        this.capturedImage = null;
        
        this.init();
    }
    
    /**
     * Initialize app
     */
    init() {
        this.video = document.getElementById('cameraFeed');
        this.canvas = document.getElementById('captureCanvas');
        
        // Event listeners
        document.getElementById('startCamera').addEventListener('click', () => this.startCamera());
        document.getElementById('stopCamera').addEventListener('click', () => this.stopCamera());
        document.getElementById('capturePhoto').addEventListener('click', () => this.capturePhoto());
        document.getElementById('searchBtn').addEventListener('click', () => this.manualSearch());
        document.getElementById('searchOnlineBtn').addEventListener('click', () => this.manualSearch(true));
        
        // Load categories on page load
        this.loadCategories();
        
        // Check camera support
        this.checkCameraSupport();
    }
    
    /**
     * Check if browser supports camera
     */
    checkCameraSupport() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            this.showError('Your browser does not support camera access. Please use Chrome, Firefox, or Edge.');
            document.getElementById('startCamera').disabled = true;
        }
    }
    
    /**
     * Start camera
     */
    async startCamera() {
        try {
            this.stream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 1280 },
                    height: { ideal: 720 },
                    facingMode: 'environment'
                },
                audio: false
            });
            
            this.video.srcObject = this.stream;
            this.video.play();
            this.isRunning = true;
            
            document.getElementById('startCamera').disabled = true;
            document.getElementById('stopCamera').disabled = false;
            document.getElementById('capturePhoto').disabled = false;
            
            this.showMessage('Camera started. Position chip code in view and capture.');
        } catch (error) {
            this.showError('Camera access denied: ' + error.message);
        }
    }
    
    /**
     * Stop camera
     */
    stopCamera() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
        }
        
        this.video.srcObject = null;
        this.isRunning = false;
        
        document.getElementById('startCamera').disabled = false;
        document.getElementById('stopCamera').disabled = true;
        document.getElementById('capturePhoto').disabled = true;
        
        this.showMessage('Camera stopped.');
    }
    
    /**
     * Capture photo from camera
     */
    capturePhoto() {
        const ctx = this.canvas.getContext('2d');
        ctx.drawImage(this.video, 0, 0, this.canvas.width, this.canvas.height);
        
        // Get image data
        this.capturedImage = this.canvas.toDataURL('image/png');
        
        // Display captured image
        document.getElementById('capturedImage').src = this.capturedImage;
        document.getElementById('capturePreview').style.display = 'block';
        
        // Process image
        this.processImage();
    }
    
    /**
     * Process captured image
     */
    async processImage() {
        if (!this.capturedImage) {
            this.showError('No image captured');
            return;
        }
        
        document.getElementById('processing').style.display = 'block';
        this.showMessage('Processing image...');
        
        try {
            const response = await fetch(`api.php?action=process_image`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'image=' + encodeURIComponent(this.capturedImage)
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.displayResults(result);
                this.showMessage(`Found ${result.codes.length} code(s)`);
            } else {
                this.showError(result.error || 'Error processing image');
            }
        } catch (error) {
            this.showError('Network error: ' + error.message);
        } finally {
            document.getElementById('processing').style.display = 'none';
        }
    }
    
    /**
     * Manual search
     */
    async manualSearch(searchOnline = false) {
        const code = document.getElementById('searchInput').value.trim();
        
        if (!code) {
            this.showError('Please enter a code');
            return;
        }
        
        document.getElementById('processing').style.display = 'block';
        this.showMessage('Searching...');
        
        try {
            const url = `api.php?action=search&code=${encodeURIComponent(code)}&online=${searchOnline ? 1 : 0}`;
            const response = await fetch(url);
            const result = await response.json();
            
            if (result.found) {
                this.displaySearchResult(result, code);
            } else {
                this.showError(`Code "${code}" not found`);
            }
        } catch (error) {
            this.showError('Search error: ' + error.message);
        } finally {
            document.getElementById('processing').style.display = 'none';
        }
    }
    
    /**
     * Display search results
     */
    displaySearchResult(result, code) {
        const resultsDiv = document.getElementById('searchResults');
        resultsDiv.innerHTML = '';
        
        const div = document.createElement('div');
        div.className = 'result-card';
        
        if (result.source === 'google' || result.source === 'duckduckgo') {
            div.innerHTML = `
                <h3>Online Results for: ${code}</h3>
                ${result.data.map(item => `
                    <div class="result-item">
                        <h4>${item.title}</h4>
                        <p>${item.snippet}</p>
                        <a href="${item.link}" target="_blank">View more</a>
                    </div>
                `).join('')}
            `;
        } else if (result.source === 'local_partial') {
            div.innerHTML = `
                <h3>Similar Codes Found: ${code}</h3>
                ${result.data.map(item => `
                    <div class="result-item">
                        <strong>Code:</strong> ${item.code}<br>
                        <strong>Category:</strong> ${item.category}<br>
                        <strong>Description:</strong> ${item.description}
                    </div>
                `).join('')}
            `;
        } else {
            const item = result.data;
            div.innerHTML = `
                <h3>✓ Match Found!</h3>
                <div class="result-item">
                    <strong>Code:</strong> ${item.code}<br>
                    <strong>Category:</strong> ${item.category}<br>
                    <strong>Description:</strong> ${item.description}<br>
                    ${item.extra.length > 0 ? '<strong>Extra Info:</strong> ' + item.extra.join(', ') : ''}
                </div>
            `;
        }
        
        resultsDiv.appendChild(div);
    }
    
    /**
     * Display results from image processing
     */
    displayResults(result) {
        const resultsDiv = document.getElementById('cameraResults');
        resultsDiv.innerHTML = '';
        
        if (result.results.length === 0) {
            resultsDiv.innerHTML = '<p>No codes found in database</p>';
            return;
        }
        
        result.results.forEach(item => {
            const div = document.createElement('div');
            div.className = 'result-card';
            
            const searchResult = item.result;
            
            if (searchResult.found) {
                if (Array.isArray(searchResult.data)) {
                    div.innerHTML = `
                        <h4>Code: ${item.code}</h4>
                        <p>Similar matches:</p>
                        ${searchResult.data.map(entry => `
                            <div class="result-item">
                                <strong>${entry.code}</strong> - ${entry.description}
                            </div>
                        `).join('')}
                    `;
                } else {
                    const entry = searchResult.data;
                    div.innerHTML = `
                        <h4>✓ ${item.code}</h4>
                        <div class="result-item">
                            <strong>Category:</strong> ${entry.category}<br>
                            <strong>Description:</strong> ${entry.description}
                        </div>
                    `;
                }
            } else {
                div.innerHTML = `
                    <h4>✗ ${item.code}</h4>
                    <p>Not found in database</p>
                `;
            }
            
            resultsDiv.appendChild(div);
        });
    }
    
    /**
     * Load categories
     */
    async loadCategories() {
        try {
            const response = await fetch('api.php?action=get_categories');
            const result = await response.json();
            
            if (result.success) {
                const select = document.getElementById('categoryFilter');
                result.categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category;
                    option.textContent = category;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }
    
    /**
     * Show message
     */
    showMessage(msg) {
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = msg;
        messageDiv.className = 'message info';
        messageDiv.style.display = 'block';
        
        setTimeout(() => {
            messageDiv.style.display = 'none';
        }, 4000);
    }
    
    /**
     * Show error
     */
    showError(msg) {
        const messageDiv = document.getElementById('message');
        messageDiv.textContent = msg;
        messageDiv.className = 'message error';
        messageDiv.style.display = 'block';
    }
}

// Initialize app when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    new ChipCameraApp();
});