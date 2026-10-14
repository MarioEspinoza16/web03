<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once(appRoot . '/views/includes/enca.php'); ?>
    <title><?php echo labEscape($data['title']); ?> | <?php echo labEscape(siteName); ?></title>
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 flex flex-col justify-between">
<div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-6">
    <header><?php require_once(appRoot . '/views/includes/menu.php'); ?></header>
    <main class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 min-h-[300px]">
        <h1 class="text-2xl font-bold mb-6 pb-4 border-b"><?php echo labEscape($data['title']); ?></h1>
