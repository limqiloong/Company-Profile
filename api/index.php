<?php
// Bootstrap the site from the frontend directory when running on Vercel.
// This keeps all includes relative to /frontend where the app now lives.
chdir(__DIR__ . '/../frontend');
require_once 'index.php';
