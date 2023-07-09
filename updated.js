$(function(){
    $("#header").load("header.html");
    $("#nav").load("nav.html");
    $("#footer").load("footer.html", function() {
        fetch('https://github.com/zhangzc11/zhangzc11.github.io/commits')
        .then(response => response.json())
        .then(data => {
            const lastUpdate = new Date(data[0].commit.author.date);
            document.getElementById('last-updated').textContent = `Last updated: ${lastUpdate.toLocaleDateString()} ${lastUpdate.toLocaleTimeString()}`;
        });
    });
});

