import { differenceInCalendarYears, differenceInHours, format, formatDistance } from 'date-fns';
import parseISO from 'date-fns/parseISO';

export default {
    install(app) {
        app.config.globalProperties.$filters = {
            date_differenceYears(date) {
                if (!date) return;
                return differenceInCalendarYears(new Date(), parseISO(date));
            },
            date_differenceHours(date) {
                if (!date) return;
                return differenceInHours(new Date(), parseISO(date));
            },
            date_difference(date) {
                if (!date) return;
                return formatDistance(new Date(), parseISO(date));
            },
            date_DATE_MONTH_YEAR(date) {
                if (!date) return;
                return format(parseISO(date), 'dd/MM/yyyy');
            },
            date_DATE_MONTH_LONG(date) {
                if (!date) return;
                return format(parseISO(date), 'MMMM dd');
            },
            date_LONG_MONTH_YEAR(date) {
                if (!date) return;
                return format(parseISO(date), 'MMMM, yyyy');
            },
            date_DAY_MONTH_YEAR(date) {
                if (!date) return;
                return format(parseISO(date), 'MMMM dd, yyyy');
            },
            DATE_MONTH_YEAR_HOUR_MINUTES(date) {
                if (!date) return;
                return format(parseISO(date), 'dd/M/yyyy h:mm aaa');
            },
            TIME_HOUR_MINUTES(date) {
                if (!date) return;
                return format(parseISO(date), 'h:mm aaa');
            },
            toMoneyFormat(value, currency = 'KES') {
                if (value == null || isNaN(value)) return '0.00';
                return new Intl.NumberFormat('en-KE', {
                    style: 'currency',
                    currency,
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }).format(value);
            },
            readableFileSize(size) {
                let units = ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
                let i = 0;
                while (size >= 1024) {
                    size /= 1024;
                    ++i;
                }
                return size.toFixed(1) + ' ' + units[i];
            },
        };
    },
};
