export interface User {
    id: number
    name: string
    email: string
    role: 'developer' | 'employer'
    avatar: string | null
    profile_completed: boolean
    created_at: string
}

export interface RegisterData {
    name: string
    email: string
    password: string
    role: 'developer' | 'employer'
}

export interface LoginData {
    email: string
    password: string
}