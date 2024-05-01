var nightmode = document.getElementById("nightmode");

 nightmode.onclick = function(){
    document.body.classList.toggle("sotetmod");
 }

 document.getElementById("new_img").onchange = function(){
   document.getElementById("imgform").submit();
 }

 document.getElementById("cover_img").onchange = function(){
    document.getElementById("coverimg").submit();
  }

 var searchInput = document.getElementById('search-input');

    searchInput.addEventListener('input', function() {
        var searchQuery = this.value.trim();

        if (searchQuery !== '') {
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    document.getElementById('search-results').innerHTML = xhr.responseText;
                }
            };
            xhr.open('GET', 'search.php?search=' + encodeURIComponent(searchQuery), true);
            xhr.send();
        } else {
            document.getElementById('search-results').innerHTML = '';
        }
    });

    searchInput.addEventListener('keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault();
        }
    });


    function toggleDropdown() {
        var dropdown = document.getElementById("ertesites-results");
        if (dropdown.style.display === "none" || dropdown.style.display === "") {
            dropdown.style.display = "block";
        } else {
            dropdown.style.display = "none";
        }
    }


    