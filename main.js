form.addEventListener("submit", function (e) {
    let hasError = false;
    let firstErrorField = null;   // 最初に見つかったエラー項目を覚えておく箱

    form.querySelectorAll("input[required], textarea[required]").forEach(function (field){
        if (!field.checkValidity()) {
            field.classList.add("error");
            hasError = true;
            if (!firstErrorField) {           // まだ何も記録されていなければ
                firstErrorField = field;        // これが「最初のエラー」として記録する
            }
        } else {
            field.classList.remove("error");
        }
    });

    const purposeChecks = form.querySelectorAll('input[name="purpose[]"]');
    const isAnyChecked = Array.from(purposeChecks).some(function (checkbox) {
        return checkbox.checked;
    });

    if (!isAnyChecked) {
        document.querySelector(".checkbox-list").classList.add("error");
        hasError = true;
        if (!firstErrorField) {
            firstErrorField = document.querySelector(".checkbox-list");
        }
    } else {
        document.querySelector(".checkbox-list").classList.remove("error");
    }

    if (hasError) {
        e.preventDefault();

        // 最初のエラー項目まで、なめらかにスクロールする
        if (firstErrorField) {
            firstErrorField.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }
    }
});