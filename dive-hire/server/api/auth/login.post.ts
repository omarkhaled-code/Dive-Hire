export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig();

  const body = await readBody(event);
  
  const response: any = await $fetch(`${config.public.apiBase}/login`, {
    method: "POST",
    body,
  });

  setCookie(event, "auth_token", response.token, {
    httpOnly: true,
    sameSite: "strict",
    maxAge: 60 * 60 * 24 * 7,
    path: "/",
  });

  return response;
});
