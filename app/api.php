<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/search.php';
require_once __DIR__ . '/ocr.php';

$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'search':
        handleSearch();
        break;
    
    case 'process_image':
        handleImageProcessing();
        break;
    
    case 'get_categories':
        handleGetCategories();
        break;
    
    case 'get_codes_by_category':
        handleGetCodesByCategory();
        break;
    
    default:
        echo json_encode(['error' => 'Invalid action']);
}

/**
 * Handle search request
 */
function handleSearch() {
    global $search;
    
    $code = $_GET['code'] ?? $_POST['code'] ?? '';
    $searchOnline = isset($_GET['online']) ? (bool)$_GET['online'] : false;
    
    if (empty($code)) {
        echo json_encode(['error' => 'Code is required']);
        return;
    }
    
    $result = $search->search($code, $searchOnline);
    echo json_encode($result);
}

/**
 * Handle image processing from camera
 */
function handleImageProcessing() {
    global $ocr, $search;
    
    $imageData = $_POST['image'] ?? '';
    
    if (empty($imageData)) {
        echo json_encode(['error' => 'No image data provided']);
        return;
    }
    
    // Process image
    $processResult = $ocr->processImage($imageData);
    
    if (isset($processResult['error'])) {
        echo json_encode($processResult);
        return;
    }
    
    // Extract codes from text
    $extractedText = $processResult['text'] ?? '';
    $codes = $ocr->extractCodes($extractedText);
    
    // Search each code
    $results = [];
    foreach ($codes as $code) {
        $searchResult = $search->search($code, false);
        $results[] = [
            'code' => $code,
            'result' => $searchResult
        ];
    }
    
    echo json_encode([
        'success' => true,
        'image' => $processResult['filename'],
        'extracted_text' => $extractedText,
        'codes' => $codes,
        'results' => $results
    ]);
}

/**
 * Get all categories from database
 */
function handleGetCategories() {
    global $db;
    
    $categories = $db->getCategories();
    echo json_encode([
        'success' => true,
        'categories' => $categories
    ]);
}

/**
 * Get codes by category
 */
function handleGetCodesByCategory() {
    global $db;
    
    $category = $_GET['category'] ?? $_POST['category'] ?? '';
    
    if (empty($category)) {
        echo json_encode(['error' => 'Category is required']);
        return;
    }
    
    $codes = $db->getCodesByCategory($category);
    echo json_encode([
        'success' => true,
        'category' => $category,
        'codes' => $codes
    ]);
}
?>
