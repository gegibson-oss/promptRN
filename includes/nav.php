<?php
declare(strict_types=1);

$isLoggedIn = isset($_SESSION['user_email']);
?>
<nav class="site-nav">
    <div class="site-nav-inner">
        <a href="/" class="logo">Prompt<span>RN</span></a>
        <div class="nav-links">
            <a href="/prompts">Prompts</a>
            <a href="/about">About</a>
            <a href="/tools/prompt-generator">Tools</a>
            <?php if ($isLoggedIn): ?>
                <a href="/members/dashboard">Dashboard</a>
                <a href="/auth/logout">Logout</a>
            <?php else: ?>
                <a href="/auth/login">Login</a>
                <a href="/auth/register" class="nav-cta">Get Access</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
