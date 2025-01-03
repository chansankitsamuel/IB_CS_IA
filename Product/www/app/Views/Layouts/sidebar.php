<nav id="sidebar">
    <div class="px-4 pt-5">
        <h5>Topics</h5>
        <ul class="list-unstyled components mb-5">
            <li>
                <a href="#" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Topics</a>
                <ul class="collapse list-unstyled" id="pageSubmenu1">
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> 1 </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> 2 </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> 3 </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> 4 </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> 5 </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> 6 </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> 7 </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Question types</a>
                <ul class="collapse list-unstyled" id="pageSubmenu2">
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> Short Question </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> Multiple Choice </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Difficulties</a>
                <ul class="collapse list-unstyled" id="pageSubmenu3">
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> Easy </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> Medium </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> Hard </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> Challenging </a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="#" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Dates</a>
                <ul class="collapse list-unstyled" id="pageSubmenu4">
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> Jeans </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> T-shirt </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> Jacket </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> Shoes </a>
                    </li>
                    <li>
                        <a href="#">
                            <span class="fa fa-chevron-right mr-2"></span> Sweater </a>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="mb-5">
            <h5>Tag Cloud</h5>
            <div class="sidebar_tag">
                <?php foreach ($tagNames as $tagName) : ?>
                    <a href="#"><?= $tagName ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="mb-5">
            <h5>Search</h5>
            <form action="#" class="subscribe-form">
                <div class="form-group d-flex">
                    <div class="icon">
                        <span class="icon-paper-plane"></span>
                    </div>
                    <input type="text" class="form-control" placeholder="Enter relevant keywords">
                </div>
            </form>
        </div>
    </div>
</nav>


<script>
    // Get all the tag elements
    var tags = document.querySelectorAll('.sidebar_tag a');


    // Function to get the selected tags from the URL
    function getSelectedTagsFromURL() {
        var urlParams = new URLSearchParams(window.location.search);
        var selectedTags = urlParams.get('tags');
        return selectedTags ? selectedTags.split(',') : [];
    }

    // Function to set the selected tags' state
    function setSelectedTagsState() {
        var selectedTags = getSelectedTagsFromURL();

        // Add selected class to the corresponding tags
        selectedTags.forEach(function(tagName) {
            tags.forEach(function(tag) {
                if (tag.textContent.trim() === tagName) {
                    tag.classList.add('selected');
                }
            });
        });
    }

    // Call the function to set the selected tags' state initially
    setSelectedTagsState();


    // Function to handle tag click event
    function handleTagClick(event) {
        // Toggle the "selected" class on the clicked tag
        this.classList.toggle('selected');

        // Call addTags function to redirect user with the updated URL
        addTags();

        // Prevent the default link behavior
        event.preventDefault();
    }

    function addTags() {
        // Get all the selected tags
        var selectedTags = document.querySelectorAll('.sidebar_tag .selected');

        // Create an array to store the selected tag names
        var tagNames = [];

        // Collect the names of the selected tags
        selectedTags.forEach(function(tag) {
            tagNames.push(tag.textContent.trim());
        });

        // Get the current URL
        var currentUrl = window.location.href;

        // Remove the fragment identifier from the URL
        var urlWithoutFragment = currentUrl.split('#')[0];

        // Create a new URL object
        var url = new URL(urlWithoutFragment);

        // Set the 'tags' parameter in the URL with selected tag names using the pipe delimiter
        url.searchParams.set('tags', tagNames.join(','));

        // Redirect the user to the updated URL
        window.location.href = url.toString();
    }

    // Attach click event listener to each tag
    tags.forEach(function(tag) {
        tag.addEventListener('click', handleTagClick);
    });

</script>