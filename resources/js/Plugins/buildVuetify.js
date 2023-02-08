import {createVuetify} from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import {md3} from "vuetify/blueprints";
import defaults from "@/Theme/defaults";
import {aliases, mdi} from "vuetify/iconsets/mdi";
import colors from "@/Theme/colors";

export default () => {
    return createVuetify({
        components,
        directives,

        blueprint: _.merge(md3, {
            defaults,
            icons: {
                defaultSet: 'mdi',
                aliases,
                sets: {
                    mdi,
                }
            },
            theme: {
                defaultTheme: 'cookbook',
                themes: {
                    cookbook: {
                        dark: false,
                        colors,
                    },
                }
            }
        }),
    })
};
