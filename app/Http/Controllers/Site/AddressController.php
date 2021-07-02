<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Users\BranchRequest;
use App\Http\Requests\Admin\Users\UpdateAddressRequest;
use App\Models\Address;
use App\Models\Site\User;
use App\Repositories\Admin\UserRepository;
use App\Repositories\Site\AddressRepository;
use App\Repositories\Site\CityRepository;
use App\Repositories\Site\ProvinceRepository;
use Illuminate\Support\Facades\DB;

class AddressController extends BaseController
{
    /**
     * @var ProvinceRepository
     */
    private $provinceRepository;
    /**
     * @var CityRepository
     */
    private $cityRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var AddressRepository
     */
    private $addressRepository;

    public function __construct(
        ProvinceRepository $provinceRepository,
        CityRepository $cityRepository,
        UserRepository $userRepository,
        AddressRepository $addressRepository
    )
    {
        $this->provinceRepository = $provinceRepository;
        $this->cityRepository = $cityRepository;
        $this->userRepository = $userRepository;
        $this->addressRepository = $addressRepository;
    }

    public function create(User $user)
    {
        $this->setPageTitle('ایجاد آدرس ها');
        $this->setCartContent();
        return view('site.dashboard.create-address');
    }

    public function store(BranchRequest $request)
    {
        $data = $request->validated();
        $user = currentUserObj();
        $this->userRepository->saveUserAddress($user, $data);
        return redirect()->route('site.addresses.index');
    }

    public function index()
    {
        $this->setPageTitle('آدرس ها');
        $this->setCartContent();
        $user = currentUserObj();
        $addresses = $this->addressRepository->getUserAddressList($user);
        $defaultAddress = $addresses->where('is_default', 1)->first();
        $otherAddresses = $addresses->where('is_default', 0)->all();
        return view('site.dashboard.address-book', compact('defaultAddress', 'otherAddresses'));
    }

    public function edit(Address $address)
    {
        $this->setPageTitle('ویرایش آدزس');
        $this->setCartContent();
        $user = currentUserObj();

        $address = $user->addresses()->where('id', $address->id)->first();
        return view('site.dashboard.edit-address', compact('address'));
    }

    public function update(Address $address, UpdateAddressRequest $request)
    {
        $data = $request->validated();
        $this->setPageTitle('ویرایش آدزس');
        $this->setCartContent();
        DB::transaction(function () use ($data, $address) {
            $user = currentUserObj();
            if ($data['is_default']) {
                $user->addresses()->where('is_default', 1)->update(['is_default' => 0]);
            }
            $address->update($data);
        });

        return redirect()->route('site.addresses.index');
    }
    public function delete(Address $address)
    {
        $this->setPageTitle('ویرایش آدزس');
        $this->setCartContent();
        $user = currentUserObj();
        $user->addresses()->where('id',$address->id)->delete();

        return redirect()->route('site.addresses.index');
    }
}
