
export function toggleElement(toggledElement, actionElement, isDefaultHidden = true) {
    const tableStudent = document.querySelector(toggledElement);
    if (tableStudent) {
        if (isDefaultHidden) {
            tableStudent.classList.add('d-none');
        }
        const button = document.querySelector(actionElement);
        if (button) {
            button.addEventListener('click', () => {
                tableStudent.classList.toggle('d-none');
            });
        }
    }
}
