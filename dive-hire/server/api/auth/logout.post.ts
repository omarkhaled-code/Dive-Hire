// import { useFetch } from "nuxt/app";

export default defineEventHandler(async (event) => {
  const config = useRuntimeConfig();
  const token = getCookie(event, "auth_token");

  if (!token) {
    throw createError({
      statusCode: 401,
      statusMessage: "Unauthorized",
    });
  }

  return await $fetch(`${config.public.apiBase}/logout`, {
    method:"POST",
    headers: {
      Authorization: `Bearer ${token}`,
    },
  });
});
