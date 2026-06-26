<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $adminPageTitle ?? 'Clevora Admin' ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <!-- Quill WYSIWYG Editor -->
  <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
  <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
  <style>
    * { box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; }
    .font-poppins { font-family: 'Poppins', sans-serif; }
    /* Quill height override */
    .ql-editor { min-height: 120px; font-size: 13px; font-family: 'Inter', sans-serif; }
    .ql-toolbar { border-top-left-radius: 8px; border-top-right-radius: 8px; background: #f9fafb; }
    .ql-container { border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; }
    /* Repeater animations */
    .repeater-row { animation: fadeIn 0.2s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: none; } }
    /* Toast */
    .toast { animation: slideIn 0.3s ease; }
    @keyframes slideIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: none; } }
  </style>
</head>
<body class="flex bg-gray-50 min-h-screen" x-data="{ sidebarOpen: true }">
