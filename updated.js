$(function(){
    $("#header").load("header.html");
    $("#nav").load("nav.html");
    $("#footer").load("footer.html", function() {

        fetch('https://api.allorigins.win/raw?url=https://api.github.com/repos/zhangzc11/zhangzc11.github.io/commits')
  .then(response => response.json())
  .then(data => {
    const lastUpdated = new Date(data[0].commit.author.date);
    document.getElementById('last-updated').textContent = 'Last updated: ' + lastUpdated;
  })
  .catch(error => console.error(error));
    });
});

