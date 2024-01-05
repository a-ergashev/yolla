function apply(id){
    const applyButton = document.querySelector("#B"+id);
    const formData = new FormData();
    formData.append("id", id);

    try {
        const response = fetch("http://localhost:8080/index.php?r=site%2Fapply",{
            method: "POST",
            body: formData,
        });
        console.log(response);
    } catch (e) {
        console.error(e);
    }
    applyButton.disabled = true;
    applyButton.style.background = "grey";
    applyButton.style.opacity = "0.5";
}