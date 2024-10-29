
window.addEventListener('DOMContentLoaded', function() {
	const categorySelect = document.getElementById('t3-categories');
	const categorySelectForm = document.getElementById('t3-category-select-form');

	categorySelect.addEventListener('change', function () {
		categorySelectForm.submit();
	});
}, false);
