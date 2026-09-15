$admin = \App\Models\User::where('role', 'admin')->first();
\Illuminate\Support\Facades\Auth::login($admin);
$request = Illuminate\Http\Request::create('/admin/users/1/edit', 'GET');
$kernel = app()->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle($request);
if ($response->getStatusCode() == 500) {
    if (isset($response->exception) && $response->exception) {
        echo $response->exception->getMessage() . "\n";
    }
}
echo "Status: " . $response->getStatusCode() . "\n";
