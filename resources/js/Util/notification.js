import eventBus from "@/Util/eventBus";
import icons from "@/Theme/icons";
import colors from "@/Theme/colors";

export default {
    info(text) {
        this.show(icons.info, colors.info, text);
    },
    success(text) {
        this.show(icons.success, colors.success, text);
    },

    warning(text) {
        this.show(icons.warning, colors.warning, text);
    },

    error(text) {
        this.show(icons.error, colors.error, text);
    },

    show(icon, color, text) {
        eventBus.$emit('notification', {icon, color, text});
    }
}
