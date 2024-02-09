var iteration = 0;
var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

setTheme();

$.ajax({
    type: "GET",
    url: "/instagram-account",
    success: function (result) {
        console.log(result);

        console.log(result.length);
        for(let i = 0; i < result.length; i++) {
            let square = document.getElementById("instagram_" + i);

            if(square) {
                square.setAttribute("src", result[i]);
            }
        }
    },
    error: function (data) {
        // Log in the console
        console.log(data);
    },
});

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

        let random = recursiveRandom(active, randomNumber(0, max), max);

        sections.get(random).classList.remove("hidden");

        setTimeout(function() {
            $("#cover").removeClass("blacked-out");
        }, 300);
    }, 300);
}

function recursiveRandom(active, random, max) {
    console.log("TEST");
    if (active !== random) {
        iteration = 0;
        return random;
    } else {
        if(iteration++ >= 5) {
            return 0;
        }

        return recursiveRandom(active, randomNumber(0, max), max);
    }
}

function randomNumber(min, max) {
    return Math.floor(Math.random() * (max - min) + min);
}

$(function() {
    $('.gallery-item, .gallery-content').waypoint(function() {
        $(this.element).addClass('pop-in');
    }, {
        offset: '100%'
    });
});

function showAdditionalContactFields()
{
    document.getElementById("date_field").classList.remove("hidden");
    document.getElementById("location_field").classList.remove("hidden");
}

$(".contact-form-submit").on("click", function() {

    let validRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    let first_name_field = document.getElementById("first_name");
    let last_name_field = document.getElementById("last_name");
    let email_field = document.getElementById("email");
    let type_field = document.getElementById("type");
    let date_field = document.getElementById("date");
    let location_field = document.getElementById("location");
    let message_field = document.getElementById("message");
    let fill_fields = $('#fill-fields');

    if(!first_name_field.value || !last_name_field.value || !email_field.value || !type_field.value || !message_field.value) {
        fill_fields.removeClass("hidden");
        fill_fields.children("span").text("Please fill all fields marked with *");
        return null;
    }

    if(!email_field.value.match(validRegex)) {
        fill_fields.removeClass("hidden");
        fill_fields.children("span").text("Please enter a valid email");
        return null;
    }

    $('.circle-loader').toggleClass('hidden');
    $('.submit-text').toggleClass('hidden');
    fill_fields.addClass("hidden");

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        type: "POST",
        url: "/contact-us",
        data: {
            first_name: first_name_field.value,
            last_name: last_name_field.value,
            email: email_field.value,
            type: type_field.value,
            date: date_field.value,
            location: location_field.value,
            message: message_field.value,
        },
        dataType: 'html',
        success: function (result) {
            $('.circle-loader').toggleClass('load-complete');
            $('.checkmark').toggle();
            $('.thanks-text').toggleClass('hidden');

            $('.contact-form-submit').toggleClass('pointer-events-none');
        },
        error: function (data) {
            // Log in the console
            console.log(data);
            $('.circle-loader').toggleClass("hidden");
            let thanks = $('.thanks-text')
                thanks.toggleClass('hidden');
                thanks.text('Something went wrong! Please email us directly at contact@philcharitou.com');

            let button = $('.contact-form-submit');
            button.addClass('pointer-events-none border-red');
        },
    });
})
