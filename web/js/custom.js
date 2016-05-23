//@todo needs a refactoring but no time yet
jQuery(document).ready(function () {
    var newFormsSum = 0;
    var $collectionHolder;
    $collectionHolder = $('panel-group');
    // add a delete link to all of the existing tag form li elements
    $('.panel-default').each(function () {
        addStateFormDeleteLink($(this));
    });

    // setup an "add a tag" link
    var $addTagLink = $('<a href="#" class="add_tag_link pull-right"><i class="fa fa-2x fa-plus-square-o"></i></a><p>');
    // add the "add a tag" anchor and li to the tags ul
    $('#accordion').after($addTagLink);
    $collectionHolder = $('#accordion');
    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addTagLink.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        addStateForm($collectionHolder, $('#accordion'), newFormsSum);
        newFormsSum++;
    });

    addFontAwesomePicker();
});


function addStateForm($collectionHolder, $newLinkLi, newFormsSum) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    var newForm = prototype.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);
    var current = $newLinkLi.find('.panel').last();
    var clone = current.clone();
    clone.insertAfter(current);
    clone.find('.panel-body').html(newForm);
    //the id dosen't matter it has just to match the link
    var rand = 'randId' + Math.floor((Math.random() * 100) + 30)

    clone.find('div:nth-child(2)').attr('id', rand);
    clone.find('a').attr('href', '#' + rand);
    clone.find('button').remove();
    clone.find('a').text('new State ' + newFormsSum);

    addStateFormDeleteLink(clone);

    addFontAwesomePicker();

    //adding color picker to new states too
    new jscolor($('.jscolor:last')[0]);

}

function addStateFormDeleteLink($tagFormLi) {
    var $removeFormA = $('<button type="button" class="close" data-dismiss="alert"><i class="fa fa-times"></i></button>');
    $tagFormLi.find('.panel-heading').find('.panel-title').append($removeFormA);

    $removeFormA.on('click', function (e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $tagFormLi.remove();
    });
}

function addFontAwesomePicker() {
    //show icon picker when defined
    if(typeof $('.icp-auto').iconpicker == 'function') {
        $('.icp-auto').iconpicker();
    }

}