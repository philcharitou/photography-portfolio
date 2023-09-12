// This is all you.

function randomizeHero()
{
    let sections = $('.hero')
    
    let max = sections.length;
    
    sections.each(function() {
        $(this).addClass("hidden");
    });
    
    let random = randomNumber(0, max);
    
    console.log(sections);
    console.log(sections.get(random));

    sections.get(random).classList.remove("hidden");
}

function randomNumber(min, max) {
    return Math.floor(Math.random() * (max - min) + min);            
}