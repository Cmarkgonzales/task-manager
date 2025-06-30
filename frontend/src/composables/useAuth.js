await api.get('/sanctum/csrf-cookie'); // Call before login/register
await api.post('/login', { email, password });
