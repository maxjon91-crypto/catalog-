<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chip Code Detector - Camera & Database Search</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <header>
            <h1>🔍 Chip Code Detector</h1>
            <p>Real-time detection and search of electronic chip codes</p>
        </header>

        <!-- Main Content -->
        <div class="content">
            <!-- Camera Section -->
            <div class="camera-section">
                <h2>📷 Camera Detection</h2>
                
                <!-- Camera Feed -->
                <video id="cameraFeed" playsinline></video>
                <canvas id="captureCanvas" width="1280" height="720"></canvas>
                
                <!-- Camera Controls -->
                <div class="button-group">
                    <button id="startCamera">▶️ Start Camera</button>
                    <button id="stopCamera" disabled>⏹️ Stop Camera</button>
                </div>
                
                <button id="capturePhoto" disabled style="width: 100%; background: #f39c12; color: white;">📸 Capture & Detect</button>
                
                <!-- Captured Image Preview -->
                <div id="capturePreview">
                    <img id="capturedImage" alt="Captured image">
                </div>

                <!-- Message Box -->
                <div id="message"></div>

                <!-- Processing Indicator -->
                <div id="processing">
                    <div class="spinner"></div>
                    <p>Processing...</p>
                </div>

                <!-- Camera Results -->
                <h3 style="margin-top: 20px; color: #333;">Detection Results:</h3>
                <div id="cameraResults"></div>
            </div>

            <!-- Search Section -->
            <div class="search-section">
                <h2>🔎 Manual Search</h2>
                
                <!-- Category Filter -->
                <div class="category-filter">
                    <label for="categoryFilter">Filter by Category:</label>
                    <select id="categoryFilter">
                        <option value="">All Categories</option>
                    </select>
                </div>

                <!-- Search Input -->
                <div class="search-input-group">
                    <input 
                        type="text" 
                        id="searchInput" 
                        placeholder="Enter chip code (e.g., ATmega328P, STM32F1)" 
                        autocomplete="off"
                    >
                </div>

                <!-- Search Buttons -->
                <div class="button-group">
                    <button id="searchBtn">🔍 Search Local DB</button>
                    <button id="searchOnlineBtn">🌐 Search Online</button>
                </div>

                <!-- Search Results -->
                <div id="searchResults"></div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="../assets/js/camera.js"></script>

    <script>
        // HTTPS redirect for camera access (if needed)
        if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
            // Uncomment if needed for production
            // location.protocol = 'https:';
        }
    </script>
</body>
</html>