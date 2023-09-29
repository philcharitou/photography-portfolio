function randomizeHero()
{
    $("#cover").addClass("blacked-out");

    let sections = $('.hero')

    let max = sections.length;
    let active = true;

    setTimeout(function() {
        sections.each(function(index, element) {
            if(!$(this).hasClass("hidden")) {
                active = index;
            }
            $(this).addClass("hidden");
        });

        random = recursiveRandom(active, randomNumber(0, max), max);

        sections.get(random).classList.remove("hidden");

        setTimeout(function() {
            $("#cover").removeClass("blacked-out");
        }, 300);
    }, 300);
}

function recursiveRandom(active, random, max) {
    if (active !== random) {
        return random;
    } else {
        return recursiveRandom(active, randomNumber(0, max), max);
    }
}

function randomNumber(min, max) {
    return Math.floor(Math.random() * (max - min) + min);
}

$(function() {
    $('.gallery-item, .creative-gallery-content').waypoint(function() {
        $(this.element).addClass('pop-in');
    }, {
        offset: '100%'
    });
});
