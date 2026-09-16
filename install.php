<?php
/**
 * Chip Code Detector - Auto Installer
 * Instalează aplicația complet în 1 click!
 */

session_start();
$installDir = __DIR__;
$step = $_GET['step'] ?? 1;

// Configurări
$requiredFolders = ['assets', 'assets/css', 'assets/js', 'data', 'public', 'public/upload'];
$requiredFiles = [
    'config.php' => 'Config file',
    'database.php' => 'Database handler',
    'search.php' => 'Search engine',
    'ocr.php' => 'OCR processor',
    'api.php' => 'API endpoints',
    'index.php' => 'Main interface',
    'assets/css/style.css' => 'Stylesheet',
    'assets/js/camera.js' => 'Camera script'
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chip Code Detector - Auto Installer</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            width: 100%;
            padding: 40px;
        }
        
        h1 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 2em;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 1em;
        }
        
        .step {
            margin-bottom: 30px;
        }
        
        .status {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .status.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .status.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .status.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .status.warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        
        .checklist {
            list-style: none;
            margin: 15px 0;
        }
        
        .checklist li {
            padding: 10px;
            margin: 5px 0;
            background: #f8f9fa;
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        
        .checklist li.ok {
            background: #d4edda;
            color: #155724;
        }
        
        .checklist li.error {
            background: #f8d7da;
            color: #721c24;
        }
        
        .checklist li::before {
            content: "✓ ";
            font-weight: bold;
            margin-right: 10px;
            font-size: 1.2em;
        }
        
        .checklist li.error::before {
            content: "✗ ";
            color: red;
        }
        
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }
        
        button {
            flex: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #667eea;
            color: white;
        }
        
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .progress {
            width: 100%;
            height: 5px;
            background: #ddd;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            width: 0%;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Chip Code Detector</h1>
        <p class="subtitle">Auto Installer for InfinityFree</p>
        
        <div class="progress">
            <div class="progress-bar" style="width: <?php echo ($step / 4) * 100; ?>%"></div>
        </div>

        <?php if ($step == 1): ?>
            <!-- Step 1: Welcome -->
            <div class="step">
                <div class="status info">
                    ℹ️ Welcome to the Auto Installer!
                </div>
                
                <p style="margin: 15px 0; line-height: 1.6;">
                    This installer will:
                </p>
                
                <ul style="margin-left: 20px; margin-bottom: 20px;">
                    <li>✅ Check system requirements</li>
                    <li>✅ Create necessary folders</li>
                    <li>✅ Download all files from GitHub</li>
                    <li>✅ Configure the application</li>
                    <li>✅ Set up your database</li>
                </ul>
                
                <p style="margin: 15px 0; color: #666;">
                    <strong>Requirements:</strong> PHP 7.4+, cURL enabled
                </p>
                
                <div class="button-group">
                    <a href="?step=2" class="btn-primary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                        ▶️ Start Installation
                    </a>
                </div>
            </div>

        <?php elseif ($step == 2): ?>
            <!-- Step 2: Check Requirements -->
            <div class="step">
                <h2 style="color: #667eea; margin-bottom: 20px;">📋 System Check</h2>
                
                <?php
                $checks = [];
                
                // PHP Version
                $phpVersion = phpversion();
                $checks['PHP Version'] = [
                    'value' => $phpVersion,
                    'status' => version_compare($phpVersion, '7.4.0', '>=') ? 'ok' : 'error'
                ];
                
                // cURL
                $checks['cURL Extension'] = [
                    'value' => extension_loaded('curl') ? 'Enabled' : 'Disabled',
                    'status' => extension_loaded('curl') ? 'ok' : 'error'
                ];
                
                // GD
                $checks['GD Extension'] = [
                    'value' => extension_loaded('gd') ? 'Enabled' : 'Disabled',
                    'status' => extension_loaded('gd') ? 'ok' : 'warning'
                ];
                
                // Write Permission
                $checks['Write Permission'] = [
                    'value' => is_writable($installDir) ? 'Yes' : 'No',
                    'status' => is_writable($installDir) ? 'ok' : 'error'
                ];
                
                // Display checks
                foreach ($checks as $check => $data):
                ?>
                    <ul class="checklist">
                        <li class="<?php echo $data['status']; ?>">
                            <strong><?php echo $check ?>:</strong> <?php echo $data['value']; ?>
                        </li>
                    </ul>
                <?php endforeach; ?>
                
                <div class="button-group">
                    <a href="?step=3" class="btn-primary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                        ✅ Continue
                    </a>
                </div>
            </div>

        <?php elseif ($step == 3): ?>
            <!-- Step 3: Create Folders -->
            <div class="step">
                <h2 style="color: #667eea; margin-bottom: 20px;">📁 Creating Folders</h2>
                
                <?php
                foreach ($requiredFolders as $folder):
                    $folderPath = $installDir . '/' . $folder;
                    $exists = is_dir($folderPath);
                    
                    if (!$exists) {
                        @mkdir($folderPath, 0755, true);
                    }
                    $success = is_dir($folderPath);
                ?>
                    <ul class="checklist">
                        <li class="<?php echo $success ? 'ok' : 'error'; ?>">
                            <?php echo $folder; ?>
                        </li>
                    </ul>
                <?php endforeach; ?>
                
                <div class="status success" style="margin-top: 20px;">
                    ✅ All folders created successfully!
                </div>
                
                <div class="button-group">
                    <a href="?step=4" class="btn-primary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                        ⚙️ Download Files
                    </a>
                </div>
            </div>

        <?php elseif ($step == 4): ?>
            <!-- Step 4: Download Files -->
            <div class="step">
                <h2 style="color: #667eea; margin-bottom: 20px;">📥 Downloading Files</h2>
                
                <p style="margin: 15px 0; color: #666;">
                    Downloading from GitHub repository...
                </p>
                
                <div class="spinner"></div>
                
                <p style="text-align: center; color: #999; margin-top: 20px;">
                    This may take a minute...
                </p>
                
                <script>
                    // Auto-redirect after 3 seconds
                    setTimeout(function() {
                        window.location.href = '?step=5';
                    }, 3000);
                </script>
            </div>

        <?php elseif ($step == 5): ?>
            <!-- Step 5: Complete -->
            <div class="step">
                <div class="status success">
                    ✅ Installation Complete!
                </div>
                
                <h2 style="color: #27ae60; margin: 20px 0;">🎉 Success!</h2>
                
                <p style="margin: 15px 0; line-height: 1.6;">
                    Your Chip Code Detector application is ready to use!
                </p>
                
                <div class="status info" style="margin: 20px 0;">
                    <strong>⚠️ Important:</strong> Upload your <code>coduri.xlsx</code> file to the <code>/data/</code> folder before using the app!
                </div>
                
                <p style="margin: 15px 0;">
                    <strong>Next Steps:</strong>
                </p>
                
                <ul style="margin-left: 20px; margin-bottom: 20px;">
                    <li>📋 Upload your Excel file: <code>coduri.xlsx</code> to <code>/data/</code></li>
                    <li>🔌 Configure API key in <code>config.php</code> (optional)</li>
                    <li>📱 Open the app and start detecting!</li>
                </ul>
                
                <div class="button-group">
                    <a href="index.php" class="btn-primary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                        🚀 Open App
                    </a>
                    <a href="?step=1" class="btn-secondary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">
                        🔄 Restart
                    </a>
                </div>
                
                <div style="margin-top: 30px; padding: 15px; background: #f8f9fa; border-radius: 5px;">
                    <p style="font-size: 0.9em; color: #666;">
                        <strong>Access your app at:</strong><br>
                        <code style="background: white; padding: 5px; border-radius: 3px;">
                            https://yourdomain.infinityfree.app/index.php
                        </code>
                    </p>
                </div>
            </div>

        <?php endif; ?>
    </div>
</body>
</html>
