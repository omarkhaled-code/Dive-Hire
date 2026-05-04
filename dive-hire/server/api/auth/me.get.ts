export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig();
  const token = getCookie(event, "auth_token");

  const response: any = await $fetch(`${config.public.apiBase}/me`, {
    method: "POST",
    headers: { Authorization: `Bearer ${token}` },
  });

  

  return response;
});
