<!--This .php file is the home page of the website -->

This .php file is the home page of the website.

<?php
include ('top.php');
?>

<!--Begin image boxes on page-->
<div id="content">
    <div id="across">
        Across.
    </div>
    <div id="article col1">
        <p>Column one</p>
    </div>
    <div id="article col2">
        <p>Column two</p>
    </div>
</div>
    
</div>
<div>
    <figure class="Recipe">
        <img alt="Description of first image" class="RecipeIntro" src="../images/aspen-trees.jpg">
        <figcaption> <b>Title of the First Recipe </b><br>
                The paragraph for the first recipe will go here... <a href="project/recipes.php">Read more</a>
            </figcaption>
    </figure>
    <figure class="Recipe">
        <img alt="Description of second image" class="RecipeIntro" src="../images/bristlecone-pine.jpg">
        <figcaption> <b>Title of the Second Recipe</b> <br>
                The paragraph for the second recipe will go here.
            </figcaption>
    </figure>
</div>
