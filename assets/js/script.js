document.addEventListener('DOMContentLoaded', function() {
    let frame;

    document.getElementById('add-gallery-image').addEventListener('click', function(e) {
        e.preventDefault();

        if (frame) {
            frame.open();
            return;
        }

        // Create the media frame
        frame = wp.media({
            title: 'Select or Upload Images',
            button: {
                text: 'Use these images'
            },
            multiple: true // Allow multiple image selection
        });

        // When images are selected
        frame.on('select', function() {
            const selection = frame.state().get('selection');
            selection.each(function(attachment) {
                const imageUrl = attachment.toJSON().url;

                // Create list item with image and remove button
                const li = document.createElement('li');
                const img = document.createElement('img');
                img.src = imageUrl;
                img.width = 300; // Set desired width
                img.height = 400; // Set desired height

                li.appendChild(img);

                const removeBtn = document.createElement('a');
                removeBtn.href = '#';
                removeBtn.classList.add('remove-image');
                removeBtn.textContent = 'Remove';
                li.appendChild(removeBtn);

                document.getElementById('gallery-images-list').appendChild(li);

                // Update the hidden input field with image URLs
                updateHiddenField();
            });
        });

        frame.open();
    });

    // Remove image on clicking the 'Remove' button
    document.getElementById('gallery-images-list').addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-image')) {
            e.preventDefault();
            const li = e.target.closest('li');
            li.parentNode.removeChild(li);
            updateHiddenField();
        }
    });

    // Update the hidden field containing all image URLs
    function updateHiddenField() {
        const imagesData = [];
        const listItems = document.querySelectorAll('#gallery-images-list li');
        listItems.forEach(function(li) {
            const imgSrc = li.querySelector('img').src;
            imagesData.push({ url: imgSrc });
        });
        document.getElementById('snaps_images').value = JSON.stringify(imagesData);
    }
});
