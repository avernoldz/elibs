// Remove Product
$(".remove-from-cart").on('click', function () {
	$(this).closest('.col-12').remove();
});

// Add to Cart and Remove From Cart
$('.addToCart').on('click', function () {
	var $this = $(this);
	$this.toggleClass('addToCart');
	if($this.hasClass('addToCart')){
		$this.text('Add To Cart');
		$this.removeClass('btn-warning');
		$this.addClass('btn-success');
	} else {
		$this.text('Remove From Cart');
		$this.addClass('btn-warning');
		$this.removeClass('btn-success');
	}
});


// Delete Row
$(".deleteRow").on('click', function () {
	$(this).closest('tr').remove();
});

function searchBooks() {
    const query = document.getElementById('searchInput').value;

    // Ensure the search input is not empty
    if (query.trim() === "") {
        alert("Please enter a book title to search.");
        return;
    }

    // Send the search query using AJAX
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'admin.bookcatalog.php?query=' + encodeURIComponent(query), true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            // Display the result
            document.getElementById('resultContainer').innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}
