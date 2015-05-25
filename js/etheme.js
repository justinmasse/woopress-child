jQuery(document).ready(function($){

	// **********************************************************************// 
    // ! Recipe
    // **********************************************************************//

    $recipe = $('.masonry-recipe');

    $recipe.each(function() {
        var recipeGrid = $(this);
        recipeGrid.isotope({ 
            itemSelector: '.recipe-item'
        });    
        $(window).smartresize(function(){
            recipeGrid.isotope({ 
                itemSelector: '.recipe-item'
            });
        });
        
        recipeGrid.parent().find('.recipe-filters a').click(function(){
            var selector = $(this).attr('data-filter');
            recipeGrid.parent().find('.recipe-filters a').removeClass('active');
            if(!$(this).hasClass('active')) {
                $(this).addClass('active');
            }
            recipeGrid.isotope({ filter: selector });
            return false;
        });
    });
    

    setTimeout(function(){
        $('.recipe').addClass('with-transition');
        $('.recipe-item').addClass('with-transition');
        $(window).resize();
    },500);

    // **********************************************************************// 
    // ! Story
    // **********************************************************************//

    $story = $('.masonry-story');

    $story.each(function() {
        var recipeGrid = $(this);
        recipeGrid.isotope({ 
            itemSelector: '.story-item'
        });    
        $(window).smartresize(function(){
            recipeGrid.isotope({ 
                itemSelector: '.story-item'
            });
        });
        
        recipeGrid.parent().find('.story-filters a').click(function(){
            var selector = $(this).attr('data-filter');
            recipeGrid.parent().find('.story-filters a').removeClass('active');
            if(!$(this).hasClass('active')) {
                $(this).addClass('active');
            }
            recipeGrid.isotope({ filter: selector });
            return false;
        });
    });
    

    setTimeout(function(){
        $('.story').addClass('with-transition');
        $('.story-item').addClass('with-transition');
        $(window).resize();
    },500);


    //anchors
    $('a[href*=#]:not([href=#])').click(function() {
        if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top - $('.fixed-header').css('height').split('px')[0] - 50
                }, 1000);
                return false;
            }
        }
    });

}); // document ready