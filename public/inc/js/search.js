$( function() {
    $( "#procedures" ).autocomplete({
      source: "/search/treatment/"
    });
});

$( function() {
    $( "#blogs" ).autocomplete({
      source: "/search/blogs/"
    });
});
