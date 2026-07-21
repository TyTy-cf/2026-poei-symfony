import {toggleElement} from './component/toggle';

window.addEventListener('load', () => {
    toggleElement(
        'section[data-profile-form]',
        'button[data-toggle-form]',
    );
});
