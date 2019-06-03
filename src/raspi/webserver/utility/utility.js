function formhash(form, password) {
  // Crea un elemento di input che verrà usato come campo di output per la password criptata.
  var p = document.createElement("input");
  // Aggiungi un nuovo elemento al tuo form.
  form.appendChild(p);
  p.name = "p";
  p.type = "hidden"
  p.value = hex_sha512(password.value);
  // Assicurati che la password non venga inviata in chiaro.
  password.value = "";
  // Come ultimo passaggio, esegui il 'submit' del form.
  form.submit();
}

function formhashChange(form, oldPassword, newPassword) {

  var pn = document.createElement("input");
  var po = document.createElement("input");

  form.appendChild(pn);
  form.appendChild(po);
  pn.name = "pn";
  pn.type = "hidden"
  pn.value = hex_sha512(newPassword.value);

  po.name = "po";
  po.type = "hidden"
  po.value = hex_sha512(oldPassword.value);

  oldPassword.value = "";
  newPassword.value = "";
}
