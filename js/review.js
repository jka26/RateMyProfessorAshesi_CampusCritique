document.getElementById('facilityForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var facility = document.getElementById('facilitySelect').value;
    var rating = document.getElementById('facilityRating').value;
    var review = document.getElementById('facilityReview').value;
    var map = {
      'Kofi Annan Library': '#reviewsLibrary',
      'Computer Labs': '#reviewsLabs',
      'Dormitories': '#reviewsDorms',
      'Cafeteria': '#reviewsCafeteria',
      'Sports Complex': '#reviewsSports'
    };
    var list = document.querySelector(map[facility] + ' .review-list');
    if(list) {
      var li = document.createElement('li');
      var strong = document.createElement('strong');
      strong.textContent = 'Student Review';
      var ol = document.createElement('ol');
      ol.setAttribute('type', 'i');
      var li1 = document.createElement('li');
      li1.textContent = rating;
      var li2 = document.createElement('li');
      li2.textContent = review;
      ol.appendChild(li1);
      ol.appendChild(li2);
      li.appendChild(strong);
      li.appendChild(ol);
      list.appendChild(li);
      var section = document.querySelector(map[facility]);
      if(section && !section.classList.contains('show')) {
        $(section).collapse('show');
      }
      this.reset();
    }
  });