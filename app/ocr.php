<?php
require_once __DIR__ . '/config.php';

class ChipOCR {
    private $uploadDir;
    
    public function __construct() {
        $this->uploadDir = __DIR__ . '/../public/upload/';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }
    
    /**
     * Process image from camera and extract text
     * Uses GD library for basic image processing
     */
    public function processImage($imageData) {
        try {
            // Decode base64 image
            if (strpos($imageData, 'data:image') === 0) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
            }
            
            $imageData = base64_decode($imageData);
            if ($imageData === false) {
                return ['error' => 'Invalid image data'];
            }
            
            // Save image
            $filename = 'chip_' . time() . '_' . uniqid() . '.png';
            $filepath = $this->uploadDir . $filename;
            
            file_put_contents($filepath, $imageData);
            
            // Process with Tesseract if available
            $extractedText = $this->extractTextWithTesseract($filepath);
            
            if (!$extractedText) {
                $extractedText = $this->extractTextWithGD($filepath);
            }
            
            return [
                'success' => true,
                'filename' => $filename,
                'text' => $extractedText,
                'filepath' => $filepath
            ];
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    /**
     * Extract text using Tesseract (if installed)
     * Install: sudo apt-get install tesseract-ocr
     */
    private function extractTextWithTesseract($imagePath) {
        if (!$this->isTesseractInstalled()) {
            return false;
        }
        
        try {
            $tempOutput = tempnam(sys_get_temp_dir(), 'ocr_');
            $command = "tesseract '$imagePath' '$tempOutput' -l eng --oem 1 --psm 6 2>/dev/null";
            
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0 && file_exists($tempOutput . '.txt')) {
                $text = file_get_contents($tempOutput . '.txt');
                unlink($tempOutput . '.txt');
                
                return $this->cleanText($text);
            }
        } catch (Exception $e) {
            error_log("Tesseract error: " . $e->getMessage());
        }
        
        return false;
    }
    
    /**
     * Extract text using GD library (fallback)
     */
    private function extractTextWithGD($imagePath) {
        try {
            $image = @imagecreatefromstring(file_get_contents($imagePath));
            if (!$image) {
                return '';
            }
            
            // Convert to grayscale
            imagefilter($image, IMG_FILTER_GRAYSCALE);
            
            // Increase contrast
            imagefilter($image, IMG_FILTER_CONTRAST, 30);
            
            // Save processed image
            $processedPath = $imagePath . '_processed.png';
            imagepng($image, $processedPath);
            imagedestroy($image);
            
            // Try Tesseract on processed image
            $text = $this->extractTextWithTesseract($processedPath);
            
            unlink($processedPath);
            
            return $text ?: $this->basicAlphanumericExtraction($imagePath);
        } catch (Exception $e) {
            error_log("GD error: " . $e->getMessage());
            return '';
        }
    }
    
    /**
     * Basic alphanumeric extraction (as fallback)
     * This is a simple pattern matcher for codes
     */
    private function basicAlphanumericExtraction($imagePath) {
        // Placeholder for basic extraction
        // In production, use more advanced OCR
        return '';
    }
    
    /**
     * Clean extracted text
     */
    private function cleanText($text) {
        // Remove extra whitespace and special characters
        $text = preg_replace('/[^A-Za-z0-9\s\-_]/', '', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }
    
    /**
     * Check if Tesseract is installed
     */
    private function isTesseractInstalled() {
        $output = [];
        exec('which tesseract 2>/dev/null', $output);
        return !empty($output);
    }
    
    /**
     * Extract alphanumeric codes from text
     */
    public function extractCodes($text) {
        $codes = [];
        
        // Match alphanumeric sequences (adjust pattern as needed)
        preg_match_all('/[A-Za-z0-9]{3,}/', $text, $matches);
        
        if (!empty($matches[0])) {
            $codes = array_unique($matches[0]);
        }
        
        return $codes;
    }
}

// Initialize OCR
$ocr = new ChipOCR();
?>
