// JavaScript Document
function toMAndJPostings() {
    window.location.href = "Mentorships & Job Postings.html";
  }
  function toAAndSHighlights() {
	window.location.href = "Alumni & Student Highlights.html";
  }
  function toCommunication() {
    window.location.href = "";
  }
  function register() {
    window.location.href = "Sign Up Page.html";
  }
  function login() {
    window.location.href = "Main Page.html";
  }
  function toDonation() {
    window.location.href = 'donation_campaigns.php';
  }
  function toFeedback() {
    window.location.href = 'feedback_list.php';
  }
  

 function showPassword() {
    var passwordField = document.getElementById("password");
    var passwordButton = document.getElementById("show-password-btn");

    if (passwordField.type === "password") {
      passwordField.type = "text";  
      passwordButton.src = "hidden.png"; 
    } else {
      passwordField.type = "password";  
      passwordButton.src = "eye.png"; 
    }
  }

function loadFooter() {
    fetch('Footer.html')
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to load footer');
            }
            return response.text();
        })
        .then(data => {
            document.getElementById('footer-placeholder').innerHTML = data;
        })
        .catch(error => {
            console.error('Error loading footer:', error);
        });
}
window.onload = loadFooter;

var swiper = new Swiper(".mySwiper", {
  spaceBetween: 30,
  pagination: {
    el: ".swiper-pagination",  
    clickable: true,           
  },
  autoplay: {
    delay: 3000, 
    disableOnInteraction: false, 
  },
  speed: 1000, 
  loop: true,  
});
