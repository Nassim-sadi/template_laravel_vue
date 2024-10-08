import { $t } from "@/plugins/i18n";
import { helpers, maxLength, minLength, required } from "@vuelidate/validators";

// Custom hex color validator

const alphaSpace = RegExp(/^[a-zA-Z\s]*$/);

const firstname = {
    required: helpers.withMessage($t("validation.required"), required),
    maxLength: 255,
    minLength: minLength(3),
    alphaSpace,
};
const lastname = {
    required: helpers.withMessage($t("validation.required"), required),
    maxLength: 255,
    minLength: minLength(3),
    alphaSpace,
};

const hexColor = helpers.regex(
    "hexColor",
    /^#?([A-Fa-f0-9]{6})$/, // Matches 6-digit hex color, with or without #
);

const Color = {
    required: helpers.withMessage($t("validation.required"), required),
    maxLength: 6,
    minLength: 6,
    hexColor,
};

export { alphaSpace, Color, firstname, hexColor, lastname };
