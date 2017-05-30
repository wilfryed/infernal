var items = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace("value"),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: $.map(entries, function (d) {
        return {
            value: d.value,
            // pass `bestPictures` to `suggestion`
            suggest: d
        }
    })
});
        
items.initialize();

var substringMatcher = function (strs) {
    return function findMatches(q, cb) {
        var matches, substringRegex;
        matches = [];
        substrRegex = new RegExp(q, 'i');
        $.each(strs, function (i, str) {
            if (substrRegex.test(str)) {
                matches.push(str);
            }
        });
        cb(matches);
    };
};

$("#the-basics .typeahead").typeahead(null, {
    name: "entries",
    display: "value",
    source: items.ttAdapter(),
    templates: {        
        suggestion: function (data) {
            // `data` : `suggest` property of object passed at `Bloodhound`
            return "<div><strong>" + data.suggest.value + "</strong></div>"
        }
    }
});

$('.typeahead').bind('typeahead:select', function (ev, data) {
    document.location.href="http://wilfryed.com/app/infernal/entry/" + data.suggest.slug;
    //console.log('Selection: ' + data.suggest.slug);
});