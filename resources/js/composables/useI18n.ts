import { I18nLocales, I18nSchema } from '../i18n';
import { useI18n } from 'vue-i18n';

export default function () {
  return useI18n<[I18nSchema], I18nLocales>();
}