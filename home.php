<!--This .php file is the home page of the website -->

This .php file is the home page of the website.

<?php
include ('top.php');
?>

<!--Begin image boxes on page-->
<div id="content">
    <div id="across">
        Donec lobortis felis in dolor efficitur sodales. Cras ante risus, ultricies 
        in maximus a, porta molestie est. Suspendisse justo leo, scelerisque et pellentesque
        ac, rutrum dapibus turpis. Fusce porttitor cursus leo eu elementum. Cras 
        hendrerit sagittis nisl, in cursus nulla egestas eget. Class aptent taciti 
        sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. 
        Praesent auctor ipsum sed dolor volutpat maximus. Suspendisse tempor quis 
        enim quis mollis. Curabitur pulvinar interdum urna ut vulputate. Donec ante
        leo, ornare vel nisi quis, imperdiet volutpat justo. Nulla facilisi. Maecenas 
        vel varius felis, a bibendum erat. Proin aliquam lectus metus, eget dictum 
        lectus malesuada quis. Praesent mauris sem, sodales ut neque eget, aliquet
        commodo ipsum. Vivamus sollicitudin, eros bibendum pulvinar pellentesque, 
        ipsum neque venenatis est, id dictum lorem urna vel nisi. Nullam convallis sodales nisi.
    </div>
    <div id="HomeImg">
        <img alt="Heading image for this page" class="HomeImg" src="../images/sand-dunes.jpg">
    </div>
</div>

<div class="title across">
    <h1>Featured Posts</h1>
</div>
<div class="row">
    <figure class="Recipe left">
        <img alt="Description of first image" class="RecipeIntro" src="../images/aspen-trees.jpg">
        <figcaption> <b>Title of the FIRST Recipe </b><br>
                The paragraph for the FIRST recipe will go here... <a href="project/recipes.php">See more recipes</a>
            </figcaption>
    </figure>
    <figure class="Recipe right">
        <img alt="Description of second image" class="RecipeIntro" src="../images/bristlecone-pine.jpg">
        <figcaption> <b>Title of the SECOND Recipe</b> <br>
                The paragraph for the SECOND recipe will go here... <a href="project/recipes.php">See more recipes</a>
            </figcaption>
    </figure>
</div>
<div class="row">
    <figure class="Recipe left">
        <img alt="Description of first image" class="RecipeIntro" src="../images/ice-in.jpg">
        <figcaption> <b>Title of the THIRD Recipe </b><br>
                The paragraph for the THIRD recipe will go here... <a href="project/recipes.php">See more recipes</a>
            </figcaption>
    </figure>
    <figure class="Recipe right">
        <img alt="Description of second image" class="RecipeIntro" src="../images/valley-view.jpg">
        <figcaption> <b>Title of the FOURTH Recipe</b> <br>
                The paragraph for the FOURTH recipe will go here... <a href="project/recipes.php">See more recipes</a>
            </figcaption>
    </figure>
</div>
