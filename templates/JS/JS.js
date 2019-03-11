
var selectedSuggestionIndex = -1;
const searchInput = document.querySelector('.search-input');
const suggestionsPanel = document.querySelector('.suggestions');

function resetSelectedSuggestion() {
  for (var i = 0; i < suggestionsPanel.children.length; i++) {
    suggestionsPanel.children[i].classList.remove('selected');
  }
}
searchInput.addEventListener('keyup', function(e) {
  if (e.key === 'ArrowDown') {
    resetSelectedSuggestion();
    selectedSuggestionIndex = (selectedSuggestionIndex < suggestionsPanel.children.length - 1) ? selectedSuggestionIndex + 1 : suggestionsPanel.children.length - 1;
    suggestionsPanel.children[selectedSuggestionIndex].classList.add('selected');
    return;
  }
  if (e.key === 'ArrowUp') {
    resetSelectedSuggestion();
    selectedSuggestionIndex = (selectedSuggestionIndex > 0) ? selectedSuggestionIndex -1 : 0;
    suggestionsPanel.children[selectedSuggestionIndex].classList.add('selected');
    return;
  }
  if (e.key === 'Enter') {
    searchInput.value = suggestionsPanel.children[selectedSuggestionIndex].innerHTML;
    suggestionsPanel.classList.remove('show');
    selectedSuggestionIndex = -1;
    return;
  }

  const input = searchInput.value;
  suggestionsPanel.innerHTML = '';
  /*const suggestions = countries.filter(function(country) {
    return country.name.toLowerCase().startsWith(input.toLowerCase());
  });*/
  fetch('http://localhost:8081/test/search?term=' + input)
  .then(response => response.json())
  .then(suggestions => {
    suggestionsPanel.classList.add('show');
    suggestions.forEach(function(suggested) {
      const div = document.createElement('div');
      div.innerHTML = suggested.name;
      div.setAttribute('class', 'suggestion');
      suggestionsPanel.appendChild(div);
    });
  });
  if (input === '') {
    suggestionsPanel.innerHTML = '';
  }
});

document.addEventListener('click', function(e) {
  if (e.target.className === 'suggestion') {
    searchInput.value = e.target.innerHTML;
    suggestionsPanel.classList.remove('show');
  }
});
