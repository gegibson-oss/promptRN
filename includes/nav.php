<?php
declare(strict_types=1);

$isLoggedIn = isset($_SESSION['user_email']);
?>
<nav class="site-nav" data-nav>
    <div class="site-nav-inner">
        <a href="/" class="logo">Prompt<span>RN</span></a>
        <button type="button" class="mobile-menu-toggle" data-nav-toggle aria-expanded="false" aria-controls="primary-nav">
            <span class="material-symbols-outlined" aria-hidden="true">menu</span>
            <span class="sr-only">Toggle navigation</span>
        </button>
        <div id="primary-nav" class="nav-links" data-nav-menu>
            <a class="nav-link" href="/prompts">Browse Conditions</a>
            <a class="nav-link" href="/tools/prompt-generator">Free Tools</a>
            <a class="nav-link" href="/about">About</a>
            <?php if ($isLoggedIn): ?>
                <a class="nav-link" href="/members/dashboard">Dashboard</a>
                <a class="nav-link" href="/members/account">Account</a>
                <a class="nav-link" href="/auth/logout">Logout</a>
            <?php else: ?>
                <a class="nav-link" href="/auth/login">Login</a>
                <a class="nav-cta" href="/auth/register">Get Full Access</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
