/**
 * @param {object} param1 
 * @returns bool
 */
function isChest(param1) {
  return param1.Property1 == "6";
}

/**
 * @param {object} param1 
 * @returns bool
 */
function isPackage(param1) {
  let property1 = parseInt(param1.Property1)
  let categoryID = parseInt(param1.CategoryID)

  if (categoryID == 11 && (property1 == "6" || property1 == "114")) {
    return true;
  }
  if (categoryID == 68 || categoryID == 72 || categoryID == 80) {
    return true;
  }
  return false;
}

const helpers = {
  fixDate(date) {
    if (date !== "") {
      var dateVal = new Date(date);
      var day = dateVal.getDate().toString().padStart(2, "0");
      var month = (1 + dateVal.getMonth()).toString().padStart(2, "0");
      var hour = dateVal.getHours().toString().padStart(2, "0");
      var minute = dateVal.getMinutes().toString().padStart(2, "0");
      var inputDate = dateVal.getFullYear() + "-" + month + "-" + day + "T" + hour + ":" + minute;

      return inputDate;
    }
    return date;
  },
  str_limit_words(string, limit, pointer = "...") {
    return string.length > limit ? string.substring(0, limit) + pointer : string;
  },
};