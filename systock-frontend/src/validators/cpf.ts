const validateCPF = (value: string): boolean | string => {
  value = value.replace(/\D/g, "");
  if (value.length !== 11) return "CPF invalido";

  if (/^(.)\1+$/.test(value)) return "CPF invalido";

  let isValid = true;
  [9, 10].forEach(function (j) {
    let soma = 0;
    for (let i = 0; i < j; i++) {
      soma += parseInt(value.charAt(i)) * (j + 2 - (i + 1));
    }
    let r = soma % 11;
    r = r < 2 ? 0 : 11 - r;

    if (r !== parseInt(value.charAt(j))) {
      isValid = false;
    }
  });
  return isValid ? true : "CPF invalido";
};

export default validateCPF;
