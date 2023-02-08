import {createI18n} from "vue-i18n";
import messages from "@/messages.json";

export default () => {
    return createI18n({
        legacy: false,
        locale: 'en',
        messages: messages
    })
};
