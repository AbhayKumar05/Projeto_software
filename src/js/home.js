/*let currentIndex = 0;
const items = document.querySelector('.carousel-items');
const totalItems = items.children.length;

document.getElementById('next').addEventListener('click', () => {
    if (currentIndex < totalItems - 1) {
        currentIndex++;
        items.style.transform = `translateX(-${currentIndex * (100 / 5)}%)`; // Assuming 5 items visible at a time
    }
    // Reset to start when reaching the cloned elements
    if (currentIndex === totalItems - 1) {
        setTimeout(() => {
            currentIndex = 0;
            items.style.transition = 'none'; // Disable transition for instant jump
            items.style.transform = `translateX(0)`;
        }, 300); // Match this time with your transition time
        setTimeout(() => {
            items.style.transition = 'transform 0.3s ease'; // Re-enable transition after the instant jump
        }, 350); // Re-enable it slightly after the transition
    }
});

document.getElementById('prev').addEventListener('click', () => {
    if (currentIndex > 0) {
        currentIndex--;
        items.style.transform = `translateX(-${currentIndex * (100 / 5)}%)`;
    }
    // Reset to end when reaching the first cloned element
    if (currentIndex === 0) {
        setTimeout(() => {
            currentIndex = totalItems - 2; // Select the last item of the original list
            items.style.transition = 'none'; // Disable transition for instant jump
            items.style.transform = `translateX(-${currentIndex * (100 / 5)}%)`;
        }, 300); // Match this time with your transition time
        setTimeout(() => {
            items.style.transition = 'transform 0.3s ease'; // Re-enable transition after instant jump
        }, 350); // Re-enable it after a slight delay
    }
});
*/


/*--------------------

var slidePosition = 1;
SlideShow(slidePosition);

// forward/Back controls
function plusSlides(n) {
  SlideShow(slidePosition += n);
}

//  images controls
function currentSlide(n) {
  SlideShow(slidePosition = n);
}

function SlideShow(n) {
  var i;
  var slides = document.getElementsByClassName("Containers");
  var circles = document.getElementsByClassName("dots");
  if (n > slides.length) {slidePosition = 1}
  if (n < 1) {slidePosition = slides.length}
  for (i = 0; i < slides.length; i++) {
      slides[i].style.display = "none";
  }
  for (i = 0; i < circles.length; i++) {
      circles[i].className = circles[i].className.replace(" enable", "");
  }
  slides[slidePosition-1].style.display = "block";
  circles[slidePosition-1].className += " enable";
} */

// hero-button
document.getElementById("hero-btn").addEventListener("click", function() {
    window.location.href = "shop.php"; 
});
 


// POP-UP 
function openModal(title, price, imageSrc, description) {
    document.getElementById("modalTitle").innerText = title;
    document.getElementById("modalPrice").innerText = price;
    document.getElementById("modalImage").src = imageSrc;
    document.getElementById("modalDescription").innerText = description;
    document.getElementById("bookModal").style.display = "block";
}

function closeModal() {
    document.getElementById("bookModal").style.display = "none";
}
 