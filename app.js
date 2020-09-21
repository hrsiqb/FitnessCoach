
var activityLevelFactor = [1.2, 1.375, 1.55, 1.725, 1.9]
var activityLevel = 1.2
function heightUnit(unit){
    let cmDiv = document.getElementById("cm-div")
    let feetDiv = document.getElementById("feet-div")
    if(unit === 'feet') {
        feetDiv.style.display = "block"
        cmDiv.style.display = "none"
    }
    else {
        feetDiv.style.display = "none"
        cmDiv.style.display = "block"
    }
}
function calculateCalories(){
    //get the gender from the radio buttons
    if(document.getElementById("male-radio").checked) var gender = "male"
    else if(document.getElementById("female-radio").checked) var gender = "female"
    //get the height unit from the radio buttons
    if(document.getElementById("feet-radio").checked) var heightUnit = "feet"
    else if(document.getElementById("cm-radio").checked) var heightUnit = "cm"
    //get the weight from the radio buttons
    if(document.getElementById("pounds-radio").checked) var weightUnit = "pound"
    else if(document.getElementById("killos-radio").checked) var weightUnit = "killo"

    let age = document.getElementById("age").value
    let weight = document.getElementById("weight").value
    let heightFeet = document.getElementById("height-feet").value
    let heightInches = document.getElementById("height-inches").value
    let heightCm = document.getElementById("height-cm").value

    let BMR = document.getElementById("BMR")
    let mCalories = document.getElementById("mCalories")
    let lCalories = document.getElementById("lCalories")
    let gCalories = document.getElementById("gCalories")
    let protein = document.getElementById("protein")
    let carbohydrates = document.getElementById("carbohydrates")
    let fat = document.getElementById("fat")
    

    // check if any input fiels is empty, alert with an error
    if((age === '') || (weight === '')){
        alert("Please fill all fields")
        return 0
    }

    if(heightUnit === 'feet'){
        if((heightFeet === '') || (heightInches === '')){
            alert("Please fill all fields")
            return 0
        }
    }
    else if(heightUnit === 'cm'){
        if(heightCm === ''){
            alert("Please fill all fields")
            return 0
        }
    }

    if(heightUnit === 'feet') {
        heightFeet = eval(parseInt(heightFeet)+heightInches/12)
        heightCm = heightFeet*30.48
    }
    if(weightUnit === 'pound') weight *= 0.453592
    
    // calculate Baisal Metabolic Rate (BMR) for male or female
    if(gender === 'male') var BMRVal = eval(88.362 + (13.397 * weight) + (4.799 * heightCm) - (5.677 * age))
    else var BMRVal = 447.593 + (9.247 * weight) + (3.098 * heightCm) - (4.330 * age)
    let mCalorieVal = BMRVal * parseFloat(activityLevel)
    let gCalorieVal = mCalorieVal + 200
    let lCalorieVal = mCalorieVal - 200
    let proteinVal = (weight * 2.20462)
    let carbohydrateVal = ((60 * gCalorieVal / 100)/4)
    let fatVal = (gCalorieVal - ((proteinVal * 4) + (carbohydrateVal * 4)))/9
    BMR.innerHTML = Math.round(BMRVal)
    mCalories.innerHTML = Math.round(mCalorieVal)
    gCalories.innerHTML = Math.round(gCalorieVal)
    lCalories.innerHTML = Math.round(lCalorieVal)
    protein.innerHTML = Math.round(proteinVal) + 'g'
    carbohydrates.innerHTML = Math.round(carbohydrateVal) + 'g'
    fat.innerHTML = Math.round(fatVal) + 'g'
    
}
function setActivityLevel(id){
    for(var i=0; i<5; i++){
        let li = document.getElementById(activityLevelFactor[i])
        if(li.className === 'active') li.className = 'activityLi'
    }
    id.className = 'active'
    activityLevel = id.id
}
// async function getLeanFactor(genderVal, ageVal){
//     if(genderVal === "male"){
//         if(ageVal >= 10 && ageVal <= 14) return 1
//         else if(ageVal >= 15 && ageVal <= 20) return 0.95
//         else if(ageVal >= 21 && ageVal <= 28) return 0.90
//         else if(ageVal > 28) return 0.85
//     }
//     else if(genderVal === "female"){
//         if(ageVal >= 14 && ageVal <= 18) return 1
//         else if(ageVal >= 19 && ageVal <= 28) return 0.95
//         else if(ageVal >= 29 && ageVal <= 38) return 0.90
//         else if(ageVal > 38) return 0.85
//     }
// }
