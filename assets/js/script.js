document.addEventListener("DOMContentLoaded", function () {
  let frame;

  document
    .getElementById("add-gallery-image")
    .addEventListener("click", function (e) {
      e.preventDefault();

      if (frame) {
        frame.open();
        return;
      }

      frame = wp.media({
        title: "Select or Upload Images",
        button: {
          text: "Use these images",
        },
        multiple: true,
      });

      frame.on("select", function () {
        const selection = frame.state().get("selection");
        selection.each(function (attachment) {
          const imageUrl = attachment.toJSON().url;

          const li = document.createElement("li");
          const img = document.createElement("img");
          img.src = imageUrl;
          img.width = 200;
          img.height = 250;

          li.appendChild(img);

          const removeBtn = document.createElement("a");
          removeBtn.href = "#";
          removeBtn.classList.add("remove-image");
          removeBtn.textContent = "Remove";
          li.appendChild(removeBtn);

          document.getElementById("gallery-images-list").appendChild(li);

          updateHiddenField();
        });
      });

      frame.open();
    });

  document
    .getElementById("gallery-images-list")
    .addEventListener("click", function (e) {
      if (e.target && e.target.classList.contains("remove-image")) {
        e.preventDefault();
        const li = e.target.closest("li");
        li.parentNode.removeChild(li);
        updateHiddenField();
      }
    });

  function updateHiddenField() {
    const imagesData = [];
    const listItems = document.querySelectorAll("#gallery-images-list li");
    listItems.forEach(function (li) {
      const imgSrc = li.querySelector("img").src;
      imagesData.push({ url: imgSrc });
    });
    document.getElementById("snaps_images").value = JSON.stringify(imagesData);
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const items = document.querySelectorAll(".snaps-carousel .carousel-item");
  const nextButton = document.querySelector(".carousel-control.next");
  const prevButton = document.querySelector(".carousel-control.prev");
  let currentItem = 0;
  const totalItems = items.length;

  function showItem(index) {
    items.forEach((item, i) => {
      item.style.display = i === index ? "block" : "none";
    });
  }

  function nextItem() {
    currentItem = (currentItem + 1) % totalItems;
    showItem(currentItem);
  }

  function prevItem() {
    currentItem = (currentItem - 1 + totalItems) % totalItems;
    showItem(currentItem);
  }

  showItem(currentItem);

  nextButton.addEventListener("click", nextItem);
  prevButton.addEventListener("click", prevItem);

  setInterval(nextItem, 3000);
});
