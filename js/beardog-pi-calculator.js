function updateMultiplierDisplay(value) {
  document.getElementById('multiplierDisplay').textContent = value;
}
function calculateSettlement() {
    // Fetching input values
    let medicalPast = parseFloat(document.getElementById('medicalPast').value) || 0;
    let medicalFuture = parseFloat(document.getElementById('medicalFuture').value) || 0;
    let lostWagesPast = parseFloat(document.getElementById('lostWagesPast').value) || 0;
    let lostWagesFuture = parseFloat(document.getElementById('lostWagesFuture').value) || 0;
    let propertyDamage = parseFloat(document.getElementById('propertyDamage').value) || 0;
    let painSufferingMultiplier = parseFloat(document.getElementById('painSuffering').value) || 2;

    // Calculating Economic Damages
    let economicDamages = medicalPast + medicalFuture + lostWagesPast + lostWagesFuture + propertyDamage;

    // Calculating Non-Economic Damages
    let nonEconomicDamages = painSufferingMultiplier * (medicalPast + medicalFuture);

    // Calculating Total Settlement Before Deductions
    let totalSettlementBeforeDeductions = economicDamages + nonEconomicDamages;

    // Calculating Final Settlement Amount
    let finalSettlementAmount = totalSettlementBeforeDeductions;

    // Displaying the result
    document.getElementById('economicDamagesOutput').value = '$' + economicDamages.toFixed(2);
    document.getElementById('nonEconomicDamagesOutput').value = '$' + nonEconomicDamages.toFixed(2);
    document.getElementById('settlementAmountOutput').value = '$' + finalSettlementAmount.toFixed(2);
}
