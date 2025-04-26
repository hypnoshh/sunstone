function setCookie(name, value, days) {
  const d = new Date();
  d.setTime(d.getTime() + (days*24*60*60*1000));
  document.cookie = name + '=' + value + ';expires=' + d.toUTCString() + ';path=/';
}
function getCookie(name) {
  const decodedCookie = decodeURIComponent(document.cookie);
  const ca = decodedCookie.split(';');
  for(let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') { c = c.substring(1); }
    if (c.indexOf(name + '=') == 0) { return c.substring(name.length + 1, c.length); }
  }
  return '';
}
function toggleTheme() {
  document.body.classList.toggle('light-mode');
  setCookie('theme', document.body.classList.contains('light-mode') ? 'light' : 'dark', 365);
}
function toggleCollapse(id) {
  const content = document.getElementById(id);
  const header = document.getElementById('btn-' + id);
  if (content.style.display === 'block') {
    content.style.display = 'none';
    header.classList.remove('active');
    setCookie(id, 'collapsed', 365);
  } else {
    content.style.display = 'block';
    header.classList.add('active');
    setCookie(id, 'expanded', 365);
  }
}
window.onload = function() {
  if (getCookie('theme') === 'light') {
    document.body.classList.add('light-mode');
  }
};
