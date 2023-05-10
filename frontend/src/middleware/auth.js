
export default function auth({ to, from, next }) {
	if (!localStorage.getItem('token')) {
	    return router.push({ name: 'login' });
	}

	return next();
}