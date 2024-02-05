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
            console.log(square);

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
