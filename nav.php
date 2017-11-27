<!-- ######################     Start of Nav   ########################## -->
<nav>
    <ol>
        <?php
        print '<li class="';
        if ($path_parts['filename'] == "home") {
            print ' activePage ';
        }
        print '">';
        print '<a href="home.php">Home</a>';
        print '</li>';
        //****
        print '<li class="';
        if ($path_parts['filename'] == "baking") {
            print ' activePage ';
        }
        print '">';
        print '<a href="baking.php">Baking</a>';
        print '</li>';
        //****
        print '<li class="';
        if ($path_parts['filename'] == 'cooking') {
            print ' activePage ';
        }
        print '">';
        print '<a href="cooking.php">Cooking</a>';
        print '</li>';
        //****
        print '<li class="';
        if ($path_parts['filename'] == 'essentials') {
            print ' activePage ';
        }
        print '">';
        print '<a href="essentials.php">Ingredients</a>';
        print '</li>';
         //****
        print '<li class="';
        if ($path_parts['filename'] == "about") {
            print ' activePage ';
        }
        print '">';
        print '<a href="about.php">About</a>';
        print '</li>';
        //****
        print '<li class="';
        if ($path_parts['filename'] == "subscribe") {
            print ' activePage ';
        }
        print '">';
        print '<a href="subscribe.php">Subscribe</a>';
        print '</li>';
        ?>
    </ol>
</nav>
